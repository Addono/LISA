<?php

use App\Models\Consumption;
use App\Models\Login;
use App\Models\LoginRole;
use App\Models\Login_User;
use App\Models\Role;
use App\Models\Role_LoginRole;
use App\Models\Transaction;
use App\Models\User;
use App\Models\User_LoginRole;
use CodeIgniter\Boot;

define('ENVIRONMENT', 'development');
define('FCPATH', dirname(__DIR__) . '/public/');
chdir(dirname(__DIR__));
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
Boot::bootConsole($paths);

function check(bool $condition, string $message): void
{
    if (! $condition) {
        throw new RuntimeException($message);
    }
}

$database = config('Database');
$database->default = array_replace($database->tests, ['DBPrefix' => '']);
$database->defaultGroup = 'default';
$db = db_connect();

foreach (glob(APPPATH . 'Models/*.php') as $file) {
    $class = 'App\\Models\\' . basename($file, '.php');
    check(class_exists($class), 'Model cannot be loaded: ' . $class);
    model($class);
}

check(Login::name() === 'login', 'Login table name changed.');
check(LoginRole::name() === 'login_role', 'Role-link table name changed.');
check(Transaction::name() === 'transaction', 'Transaction table name changed.');

$db->query('CREATE TABLE login (login_id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT UNIQUE, password TEXT)');
$db->query('CREATE TABLE user (login_id INTEGER PRIMARY KEY, first_name TEXT, last_name TEXT, email TEXT)');
$db->query('CREATE TABLE role (role_id INTEGER PRIMARY KEY AUTOINCREMENT, role_name TEXT UNIQUE)');
$db->query('CREATE TABLE login_role (login_id INTEGER, role_id INTEGER)');
$db->query('CREATE TABLE consumption (login_id INTEGER PRIMARY KEY, amount INTEGER DEFAULT 0)');
$db->query('CREATE TABLE "transaction" (transaction_id INTEGER PRIMARY KEY AUTOINCREMENT, subject_id INTEGER, author_id INTEGER, amount INTEGER, delta INTEGER, type TEXT, time TIMESTAMP DEFAULT CURRENT_TIMESTAMP)');

check(model(Login::class)->add('migration-user', 'migration-password'), 'Login creation failed.');
$loginId = (int) model(Login::class)->getLoginIdFromUsername('migration-user');
check(model(Login::class)->checkUsernamePasswordCredentials('migration-user', 'migration-password'), 'Correct password rejected.');
check(! model(Login::class)->checkUsernamePasswordCredentials('migration-user', 'wrong-password'), 'Wrong password accepted.');
check(! model(Login::class)->checkUserIdPasswordCredentials(-1, 'password'), 'Missing login accepted.');
check(model(User::class)->add($loginId, 'Migration', 'User', 'migration@example.com'), 'User creation failed.');
check(model(Login_User::class)->exists($loginId), 'Login/user join failed.');
check(model(Role::class)->add(Role::ROLE_USER), 'Role creation failed.');
$roleId = model(Role::class)->getRoleIdFromRoleName(Role::ROLE_USER);
check(model(LoginRole::class)->add($loginId, $roleId), 'Role assignment failed.');
check(model(Role_LoginRole::class)->userHasRole($loginId, Role::ROLE_USER), 'Role check failed.');
check(! model(Role_LoginRole::class)->userHasRole($loginId, Role::ROLE_ADMIN), 'Unexpected admin permission.');
check(count(model(User_LoginRole::class)->getUsersWithRole($roleId)) === 1, 'Users-by-role query failed.');
check((int) model(Consumption::class)->get($loginId) === 0, 'New balance was not initialized.');
check(model(Consumption::class)->change($loginId, $loginId, 5, Transaction::TYPE_UPGRADE), 'Credit change failed.');
check(model(Consumption::class)->change($loginId, $loginId, -1, Transaction::TYPE_CONSUME), 'Consumption failed.');
check((int) model(Consumption::class)->get($loginId) === 4, 'Balance is incorrect.');
check(count(model(Transaction::class)->getAllFromSubject($loginId)) === 2, 'Transaction logging failed.');
check(model(Transaction::class)->getConsumeCountForSubject($loginId) === 1, 'Consumption count is incorrect.');
check(model(Transaction::class)->getSumDeltaSubjectIdWithinTop($loginId, 10), 'Leaderboard membership query failed.');
check(count(model(\App\Models\User_Transaction::class)->getSumDeltaForAllSubjectId(false, 10)) === 1, 'Leaderboard user join failed.');
check(count(model(\App\Models\Login_User_LoginRole_Role::class)->getAllUserData()) === 1, 'Admin user overview query failed.');

$db->query('CREATE TABLE login_reset (login_id INTEGER PRIMARY KEY, reset_key TEXT UNIQUE, date TIMESTAMP DEFAULT CURRENT_TIMESTAMP)');
$key = model(\App\Models\LoginReset::class)->add($loginId);
check(is_string($key), 'Password reset key was not created.');
check(model(\App\Models\LoginReset_User::class)->updatePassword($key, 'new-password'), 'Password reset failed.');
check(! model(\App\Models\LoginReset::class)->exists($key), 'Password reset key can be reused.');
check(model(Login::class)->checkUsernamePasswordCredentials('migration-user', 'new-password'), 'Password reset did not update credentials.');

service('language')->setLocale('en');
check(lang('Lisa.login_username') === 'Username', 'English translations failed.');
service('language')->setLocale('nl');
check(lang('Lisa.login_username') !== 'Lisa.login_username', 'Dutch translations failed.');

$modelVersions = (new ReflectionClass(\App\Controllers\Install::class))->getMethod('getModelVersion');
$db->query('CREATE TABLE model_versions (model_name TEXT, table_name TEXT, version INTEGER)');
$db->table('model_versions')->insert(['model_name' => 'Login', 'table_name' => 'login', 'version' => 2]);
$installer = new \App\Controllers\Install();
(new ReflectionProperty($installer, 'db'))->setValue($installer, $db);
check((int) $modelVersions->invoke($installer, Login::class) === 2, 'Historical model version keys changed.');

check((new \App\Libraries\Graph())->getGraphForTransactions([]) === '', 'Empty transaction history generated a graph.');
\Config\Services::injectMock('request', \Config\Services::incomingrequest(null, false));
$bufferLevel = ob_get_level();
$javascript = (new \App\Libraries\Transactions())->getJavascript('default');
check(str_contains($javascript, 'purchase = function'), 'Consumption JavaScript rendering failed.');
check(ob_get_level() === $bufferLevel, 'Consumption JavaScript leaked an output buffer.');
$consumeHtml = view('page/default/consume-header', [
    'myId' => $loginId,
    'fields' => ['login_id' => 'login_id', 'first_name' => 'first_name', 'last_name' => 'last_name', 'amount' => 'amount'],
    'tabs' => ['test' => ['title' => 'Test', 'icon' => '', 'users' => [
        ['login_id' => $loginId, 'first_name' => 'Migration', 'last_name' => 'User', 'amount' => 4],
    ]]],
    'winners' => [],
    'transactionsUi' => new \App\Libraries\Transactions(),
]);
check(str_contains($consumeHtml, 'id="snackbar"'), 'Consumption view failed to render its transaction controls.');
$leaderboardHtml = view('page/default/leaderboard-header', [
    'fields' => ['login_id' => 'login_id', 'first_name' => 'first_name', 'last_name' => 'last_name', 'sum' => 'sum'],
    'entries' => [['login_id' => $loginId, 'first_name' => 'Migration', 'last_name' => 'User', 'sum' => -1]],
    'streakLength' => [],
    'transactionsLibrary' => new \App\Libraries\Transactions(),
]);
check(str_contains($leaderboardHtml, 'id="snackbar"'), 'Leaderboard view failed to render its transaction controls.');

echo "CI4 migration regression checks passed on PHP " . PHP_VERSION . ".\n";