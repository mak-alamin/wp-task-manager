import React, { useState, useEffect } from 'react';
const TaskList = ({tasks, fetchTasks, setCurrentTaskId, setShowCreateForm, setShowUpdateForm}) => {
  useEffect(() => {
    fetchTasks();
  }, []);

  const handleUpdate = async (taskId) => {
    setCurrentTaskId(taskId);
    setShowCreateForm(false);
    setShowUpdateForm(true);
  }

  const handleDelete = async (taskId) => {
    if(!confirm("Are you sure you want to delete?")){
      return;
    }

    let url = makWPtmData.restRoot + `wptm/v1/delete-task/${taskId}`;

    try {
      const response = await fetch(url, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          "X-WP-Nonce": makWPtmData?.nonce,
          // Include any additional headers if needed
        },
      });

      if (response.ok) {
        const result = await response.json();
        console.log(result); // Task deleted successfully
     
        fetchTasks();
      } else {
        const error = await response.json();
        console.error(error); // Failed to delete task
      }
    } catch (error) {
      console.error('Error:', error);
    }
  };

  return (
    <>
    <h4>All Tasks</h4>
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Duration</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>

        {(tasks.length == 0) && <p>No task founds.</p>}

        {(tasks.length != 0) && tasks.map((task) => (
          <tr key={task.id}>
            <td>{task.title}</td>
            <td>{task.description}</td>
            <td>{task.duration} hours</td>
            <td>{task.status}</td>
            <td>
              <button class="btn btn-outline-primary" onClick={() => handleUpdate(task.id)}>Edit</button>
              <button class="btn btn-outline-danger ms-2" onClick={()=> handleDelete(task.id)}>Delete</button>
            </td>
          </tr>
        ))}
        </tbody>
      </table>
      </>
  );
};

export default TaskList;
