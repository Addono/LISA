# Get all node dependencies
FROM node:22 AS node-builder

WORKDIR /application

COPY package.json package-lock.json ./

RUN npm ci --omit=dev

# Add all static files to our NGINX image
FROM nginx:alpine

WORKDIR /application

COPY --from=node-builder /application/node_modules/ ./node_modules/
COPY public/ ./public/
COPY manifest.webmanifest robots.txt ./public/

EXPOSE 8080
