FROM mysql:latest
ENV MYSQL_DATABASE scandiwebAssignment
COPY init.sql /docker-entrypoint-initdb.d/
EXPOSE 3306