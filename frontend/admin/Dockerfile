FROM node:latest AS base

WORKDIR /app

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

RUN set -x && npm install -g pnpm

EXPOSE 3121

FROM base AS dev

ADD ./ /app

RUN pnpm install

ENTRYPOINT ["/entrypoint.sh"]

CMD ["pnpm", "run", "dev"]
