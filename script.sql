create table users
(
    id                 int auto_increment
        primary key,
    username           varchar(50)                      not null,
    password           varchar(255)                     not null,
    email              varchar(100)                     not null,
    role               enum ('admin', 'author', 'user') not null,
    reset_token        varchar(255)                     null,
    reset_token_expiry datetime                         null
);

create table posts
(
    id           int auto_increment
        primary key,
    title        varchar(255)                          not null,
    content      text                                  not null,
    image        varchar(255)                          null,
    publish_date timestamp default current_timestamp() not null,
    author_id    int                                   null,
    constraint posts_ibfk_1
        foreign key (author_id) references users (id)
);

create table comments
(
    id           int auto_increment
        primary key,
    post_id      int                                   null,
    user_id      int                                   null,
    content      text                                  not null,
    publish_date timestamp default current_timestamp() not null,
    constraint comments_ibfk_1
        foreign key (post_id) references posts (id),
    constraint comments_ibfk_2
        foreign key (user_id) references users (id)
);

create index post_id
    on comments (post_id);

create index user_id
    on comments (user_id);

create index author_id
    on posts (author_id);


