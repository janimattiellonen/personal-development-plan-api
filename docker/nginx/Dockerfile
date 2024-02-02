FROM nginx:1.25.2-alpine

RUN apk add nano

RUN rm /etc/nginx/conf.d/default.conf
COPY localhost.conf /etc/nginx/conf.d/localhost.conf
VOLUME /var/www/app
