FROM mysql:5.7

COPY ./database/database.sqlite /dump/database.sqlite

CMD sqlite3 dump/database.sqlite > dump.sql && exit
