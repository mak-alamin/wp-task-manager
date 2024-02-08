import React, { useState } from "react";
import TaskList from "./components/TaskList";
import CreateTaskForm from "./components/CreateTaskForm";

const App = () => {
  const [showForm, setShowForm] = useState(false);

  const [tasks, setTasks] = useState([]);

  const fetchTasks = async () => {
    let url = makWPtmData.restRoot + "wptm/v1/get-tasks";
    try {
      const response = await fetch(url);

      if (response.ok) {
        const result = await response.json();
        setTasks(result);
      } else {
        console.error("Failed to fetch tasks:", response.statusText);
      }
    } catch (error) {
      console.error("Error:", error);
    }
  };

  return (
    <div>
      <h2 className="app-title text-success">Task Manager</h2>
      <hr />

      <button
        class="btn btn-outline-primary mb-3"
        onClick={() => setShowForm(true)}
      >
        Create New Task
      </button>

      {showForm && <button
        class="btn btn-outline-success ms-2 mb-3"
        onClick={() => setShowForm(false)}
      >
        All Tasks
      </button>}

      {showForm && <CreateTaskForm fetchTasks={fetchTasks}></CreateTaskForm>}

      <TaskList tasks={tasks} fetchTasks={fetchTasks}></TaskList>
    </div>
  );
};

export default App;
