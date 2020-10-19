<?php
	class Post {
		private $db;
		
		public function __construct() {
			$this->db = new Database();
		}
		
		# find posts by user id
		public function getPosts() {
			$this->db->query("CALL spGetPosts()");
			$results = $this->db->resultSet();
			
			return $results;
		}
		
		public function addPost($data) {
			$this->db->query("CALL spAddPost(:title, :body, :user_id)");
			
			$params = [
				":title"   => $data->title->value, 
				":body"    => $data->body->value, 
				":user_id" => $data->user_id->value
			];
			
			$this->db->bindArray($params);
			
			# execute
			if ($this->db->execute()) {
				return true;
			}
			else {
				return false;
			}
		}
		
		public function updatePost($data) {
			$this->db->query("CALL spUpdatePost(:id, :title, :body)");
			
			$params = [
				":id"      => $data->id->value,
				":title"   => $data->title->value, 
				":body"    => $data->body->value
			];
			
			$this->db->bindArray($params);
			
			# execute
			if ($this->db->execute()) {
				return true;
			}
			else {
				return false;
			}
		}
		
		public function deletePost($id) {
			$this->db->query("CALL spDeletePost(:id)");
			$this->db->bind(":id", $id);
			
			# execute
			if ($this->db->execute()) {
				return true;
			}
			else {
				return false;
			}
		}
		
		public function getPostById($id) {
			$this->db->query("CALL spGetPostById(:id)");
			$this->db->bind(":id", $id);
			$row = $this->db->single();
			
			return $row;
		}
	}
?>