FROM node:latest AS base

WORKDIR /app

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

RUN set -x && npm install -g pnpm

FROM base AS dev

ADD ./ /app

RUN pnpm install

ENTRYPOINT ["/entrypoint.sh"]

CMD ["pnpm", "run", "dev"]

#CMD ["nginx", "-g", "daemon off;"]


#FROM base AS prod

#WORKDIR /app/public

#RUN npm install --force && npm run build

#COPY --from=base /frontend/build/ ./

#CMD ["npm", "build"]
