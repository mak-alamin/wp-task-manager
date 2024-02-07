import React from "react";

const TaskForm = () => {
  return (
    <form className="taskForm">
      <div className="mb-3">
        <label for="taskTitle" className="form-label">
          Task Title
        </label>
        <input type="email" className="form-control" id="taskTitle" />
      </div>

      <div className="mb-3">
        <label for="taskDescription" className="form-label">
          Description
        </label>
        <textarea
          className="form-control"
          id="taskDescription"
          rows="3"
        ></textarea>
      </div>

      <div className="mb-3">
        <label for="taskDuration" className="form-label">
          Duration
        </label>
        <input type="text" className="form-control" id="taskDuration" />
      </div>

      <div className="mb-3">
        <label for="taskStatus" className="form-label">
          Status
        </label>
        <select class="form-select" id="taskStatus">
          <option value="pending">Pending</option>
          <option value="in-progress">In Progress</option>
          <option value="completed">Completed</option>
        </select>
      </div>
      <button type="submit" className="btn btn-primary">
        Create Task
      </button>
    </form>
  );
};

export default TaskForm;
