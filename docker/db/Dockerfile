FROM mariadb
ADD ./structure.sql /tmp/structure.sql
ADD ./datas.sql /tmp/datas.sql
ADD 00-import.sh /docker-entrypoint-initdb.d/
