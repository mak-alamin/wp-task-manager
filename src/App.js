import React, { useState } from "react";
import TaskList from "./components/TaskList";
import CreateTaskForm from "./components/CreateTaskForm";
import UpdateTaskForm from "./components/UpdateTaskForm";

const App = () => {
  const [showCreateForm, setShowCreateForm] = useState(false);
  const [showUpdateForm, setShowUpdateForm] = useState(false);
  const [currentTaskId, setCurrentTaskId] = useState(0);

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
        onClick={() => {
            setShowCreateForm(true); 
            setShowUpdateForm(false);
        }}
      >
        Create New Task
      </button>

      {(showCreateForm || showUpdateForm ) && (
        <button
          class="btn btn-outline-success ms-2 mb-3"
          onClick={() => {
            setShowCreateForm(false);
            setShowUpdateForm(false);
          }}
        >
          All Tasks
        </button>
      )}

      {showCreateForm && (
        <CreateTaskForm fetchTasks={fetchTasks}></CreateTaskForm>
      )}

      {showUpdateForm && (
        <UpdateTaskForm taskId={currentTaskId} fetchTasks={fetchTasks}></UpdateTaskForm>
      )}

      <TaskList
        tasks={tasks}
        fetchTasks={fetchTasks}
        setCurrentTaskId={setCurrentTaskId}
        setShowCreateForm={setShowCreateForm}
        setShowUpdateForm={setShowUpdateForm}
      ></TaskList>
    </div>
  );
};

export default App;
