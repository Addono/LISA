# Get all node dependencies
FROM node:12 as node-builder

WORKDIR /application

COPY package.json package-lock.json ./

RUN npm install --only=prod

# Add all static files to our NGINX image
FROM nginx:alpine

WORKDIR /application

COPY --from=node-builder /application/node_modules/ ./
COPY public/ manifest.webmanifest robots.txt ./

EXPOSE 8080
