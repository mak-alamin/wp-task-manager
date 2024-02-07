import React, { useState } from "react";
import { useForm } from "react-hook-form";

const TaskForm = () => {
  const {
    register,
    handleSubmit,
    watch,
    formState: { errors },
  } = useForm();

  const onSubmit = async (data) => {
    console.log(data);

    let formData = {
        title: data?.taskTitle,
        description: data?.taskDescription,
        duration: data?.taskDuration,
        status: data?.taskStatus,
      };

    try {
      const response = await fetch(
        "http://localhost/wp-test/wp-json/wptm/v1/create-task",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            // Include any additional headers if needed
          },
          body: JSON.stringify(formData),
        }
      );

      if (response.ok) {
        const result = await response.json();
        console.log(result); // Task created successfully
      } else {
        const error = await response.json();
        console.error(error); // Failed to create task
      }
    } catch (error) {
      console.error("Error:", error);
    }
  };

  return (
    <form className="taskForm mb-5" onSubmit={handleSubmit(onSubmit)}>
      <div className="mb-3">
        <label for="taskTitle" className="form-label">
          Task Title
        </label>
        <input
          type="text"
          className="form-control"
          {...register("taskTitle", { required: true })}
        />
      </div>

      <div className="mb-3">
        <label for="taskDescription" className="form-label">
          Description
        </label>
        <textarea
          className="form-control"
          rows="3"
          {...register("taskDescription")}
        ></textarea>
      </div>

      <div className="mb-3">
        <label for="taskDuration" className="form-label">
          Duration (hours)
        </label>
        <input
          type="number"
          className="form-control"
          defaultValue="60"
          {...register("taskDuration")}
        />
      </div>

      <div className="mb-3">
        <label for="taskStatus" className="form-label">
          Status
        </label>
        <select class="form-select" {...register("taskStatus")}>
          <option value="pending">Pending</option>
          <option value="in-progress">In Progress</option>
          <option value="completed">Completed</option>
        </select>
      </div>
      <input type="submit" value="Create Task" className="btn btn-primary" />
    </form>
  );
};

export default TaskForm;
