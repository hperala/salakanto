create table names (
name_id int auto_increment primary key,
title varchar(70),
text text,
index_coverage text,
other_coverage text,
secondary text,
notes text,
created datetime not null,
updated timestamp default current_timestamp on update current_timestamp
);

create table instances (
instance_id int auto_increment primary key,
name_id int unsigned not null,
source_id int unsigned not null,
source_elaboration varchar(255),
text varchar(255) not null,
note text,
ref varchar(255),
translation text,
etymology text,
updated timestamp default current_timestamp on update current_timestamp
);

create table sources (
source_id int auto_increment primary key,
abbreviation varchar(50) not null,
year smallint not null,
text text,
updated timestamp default current_timestamp on update current_timestamp
);

create table users (
user_id int auto_increment primary key,
name text not null,
passwd text not null,
user_type_id int unsigned not null,
created datetime not null
);

create table user_types (
user_type_id int primary key,
name varchar(256)
);

create table settings (
id int auto_increment primary key,
allow_signup bit not null
);

insert into user_types(user_type_id, name) values 
(0, 'User'),
(1, 'Administrator'),
(2, 'Root');

insert into settings(allow_signup) values (b'0');

