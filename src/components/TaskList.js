import React, { useState, useEffect } from 'react';
const TaskList = () => {
  const [tasks, setTasks] = useState([]);

  let url = makWPtmData.restRoot + 'wptm/v1/get-tasks';

  useEffect(() => {
    const fetchTasks = async () => {
      try {
        const response = await fetch(url);

        if (response.ok) {
          const result = await response.json();
          setTasks(result);
        } else {
          console.error('Failed to fetch tasks:', response.statusText);
        }
      } catch (error) {
        console.error('Error:', error);
      }
    };

    fetchTasks();
  }, []); // The empty dependency array ensures that the effect runs once after the component mounts

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
        {tasks.map((task) => (
          <tr key={task.id}>
            <td>{task.title}</td>
            <td>{task.description}</td>
            <td>{task.duration}</td>
            <td>{task.status}</td>
            <td>
              <button class="btn btn-outline-primary">Edit</button>
              <button class="btn btn-outline-danger ms-2">Delete</button>
            </td>
          </tr>
        ))}
        </tbody>
      </table>
      </>
  );
};

export default TaskList;
