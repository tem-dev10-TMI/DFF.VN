<?php
require_once  __DIR__ . '/../model/commentmodel.php';

class CommentController
{

    // Thêm comment mới
    public function addComment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $article_id = $_POST['article_id'] ?? null;
            $user_id = $_POST['user_id'] ?? null;
            $content = $_POST['content'] ?? '';
            $parent_id = $_POST['parent_id'] ?? null;

            if ($article_id && $user_id && $content) {
                $result = CommentsModel::addComment($article_id, $user_id, $content, $parent_id);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Comment added successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to add comment.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
            }
        }
    }

    // Lấy tất cả comment của một bài viết
    public function getComments($article_id)
    {
        $comments = CommentsModel::getCommentsByArticle($article_id);
        echo json_encode($comments);
    }

    // Lấy trả lời của một comment
    public function getReplies($parent_id)
    {
        $replies = CommentsModel::getReplies($parent_id);
        echo json_encode($replies);
    }

    // Cập nhật comment
    public function updateComment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $content = $_POST['content'] ?? '';

            if ($id && $content) {
                $result = CommentsModel::updateComment($id, $content);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Comment updated successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update comment.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
            }
        }
    }

    // Xóa comment
    public function deleteComment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;

            if ($id) {
                $result = CommentsModel::deleteComment($id);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Comment deleted successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to delete comment.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Missing comment ID.']);
            }
        }
    }

    // Tăng upvote
    public function upvoteComment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;

            if ($id) {
                $result = CommentsModel::upvoteComment($id);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Comment upvoted successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to upvote comment.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Missing comment ID.']);
            }
        }
    }
            // Trả lời một comment (dạng @tennguoidung)
     public function replyComment(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $article_id = $_POST['article_id'] ?? null;
            $user_id    = $_POST['user_id'] ?? null;      // người trả lời
            $content    = $_POST['content'] ?? '';
            $parent_id  = $_POST['parent_id'] ?? null;    // id comment gốc
            $mention    = $_POST['mention'] ?? '';        // tên người được nhắc

            if ($article_id && $user_id && $content && $parent_id && $mention) {
                // Tự động gắn @mention vào đầu nội dung
                $finalContent = '@' . $mention . ' ' . $content;

                $result = CommentsModel::addComment($article_id, $user_id, $finalContent, $parent_id);
                if ($result) {
                    echo json_encode([
                        'status'  => 'success',
                        'message' => 'Reply added successfully.',
                        'data'    => [
                            'content' => $finalContent,
                            'parent_id' => $parent_id
                        ]
                    ]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to add reply.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
            }
        }
    }
}
