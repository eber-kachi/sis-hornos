FROM node:16-alpine AS build
WORKDIR /app
COPY package*.json ./
RUN npm install -g @angular/cli @angular-devkit/build-angular && npm install
# RUN npm install
COPY . .
# RUN npm run build
EXPOSE 4200
CMD ["npm", "start"]

