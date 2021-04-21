FROM ubuntu:20.04

MAINTAINER @bigger777

ADD . /app

WORKDIR /app

RUN apt update
RUN apt install -y make gcc default-libmysqlclient-dev

CMD make start_server
