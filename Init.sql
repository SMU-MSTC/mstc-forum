-- Run this file after initialized.
INSERT INTO users (user_name, user_password, user_is_admin)
VALUES('admin', md5(md5('admin')), true);

INSERT INTO users (user_name, user_password)
VALUES('user1', md5(md5('user')));

INSERT INTO users (user_name, user_password)
VALUES('user2', md5(md5('user')));

INSERT INTO boards (board_name, board_intro)
VALUES ('First Test', 'First test board.');

INSERT INTO boards (board_name, board_intro)
VALUES ('Second Test', 'Second test board.');

INSERT INTO threads (user_id, board_id, thread_title, thread_content, thread_time)
VALUES (1, 1, 'First Thread', 'This is a test thread.', localtimestamp);

INSERT INTO replies (user_id, thread_id, reply_content, reply_time)
VALUES (2, 1, 'Reply test.', localtimestamp);

INSERT INTO replies (user_id, thread_id, reply_content, reply_time, reply_is_reply, reply_reply_id)
VALUES (1, 1, 'Reply test of another reply.', localtimestamp, true, 1);

INSERT INTO threads (user_id, board_id, thread_title, thread_content, thread_time)
VALUES (3, 1, 'Second Thread', 'This is another test thread.', localtimestamp);