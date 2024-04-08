create table tasks
(
    id          bigint unsigned auto_increment
        primary key,
    title       varchar(255)                                             not null,
    description text                                                     not null,
    status      enum ('important', 'simple', 'unimportant', 'nonurgent') not null,
    created_at  timestamp                                                null,
    updated_at  timestamp                                                null
)


