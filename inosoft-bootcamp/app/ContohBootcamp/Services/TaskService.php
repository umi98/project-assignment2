<?php

namespace App\ContohBootcamp\Services;

use App\ContohBootcamp\Repositories\TaskRepository;

class TaskService {
	private TaskRepository $taskRepository;

	public function __construct() {
		$this->taskRepository = new TaskRepository();
	}

	/**
	 * NOTE: untuk mengambil semua tasks di collection task
	 */
	public function getTasks()
	{
		$tasks = $this->taskRepository->getAll();
		return $tasks;
	}

	/**
	 * NOTE: menambahkan task
	 */
	public function addTask(array $data)
	{
		$taskId = $this->taskRepository->create($data);
		return $taskId;
	}

	/**
	 * NOTE: UNTUK mengambil data task
	 */
	public function getById(string $taskId)
	{
		$task = $this->taskRepository->getById($taskId);
		return $task;
	}

	/**
	 * NOTE: untuk update task
	 */
	public function updateTask(array $editTask, array $formData)
	{
		if(isset($formData['title']))
		{
			$editTask['title'] = $formData['title'];
		}

		if(isset($formData['description']))
		{
			$editTask['description'] = $formData['description'];
		}

		$id = $this->taskRepository->save( $editTask);
		return $id;
	}

	/**
	 * NOTE: untuk delete task
	 */
	public function deleteTask(string $id)
	{
		$taskId = $this->taskRepository->deleteTask($id);
		return $taskId;
	}

	/**
	 * NOTE: untuk assign task
	 */
	public function assignTask(array $editTask, string $formData)
	{
		$editTask['assigned'] = $formData;
		$id = $this->taskRepository->save( $editTask);
		return $id;
	}

	/**
	 * NOTE: untuk unassign task
	 */
	public function unassignTask(array $editTask)
	{
		$editTask['assigned'] = null;
		$id = $this->taskRepository->save( $editTask);
		return $id;
	}
	
	/**
	 * NOTE: untuk add subtask
	 */
	public function addSubTask(array $editTask, array $formData)
	{
		$title = $formData['title'];
		$description = $formData['description'];

		$subtasks = isset($editTask['subtasks']) ? $editTask['subtasks'] : [];

		$subtasks[] = [
			'_id'=> (string) new \MongoDB\BSON\ObjectId(),
			'title'=>$title,
			'description'=>$description
		];

		$editTask['subtasks'] = $subtasks;

		$id = $this->taskRepository->save($editTask);
		return $id;
	}

	/**
	 * NOTE: untuk hapus subtask
	 */
	public function deleteSubTask(array $editTask, string $idSubTask)
	{
		$subTasks = isset($editTask['subtasks']) ? $editTask['subtasks'] : [];

		// Pencarian dan penghapusan subtask
		$subtasks = array_filter($subTasks, function($subtask) use($idSubTask) {
			if($subtask['_id'] == $idSubTask)
			{
				return false;
			} else {
				return true;
			}
		});

		$subtasks = array_values($subtasks);
		$editTask['subtasks'] = $subtasks;

		$id = $this->taskRepository->save( $editTask);
		return $id;
	}
}