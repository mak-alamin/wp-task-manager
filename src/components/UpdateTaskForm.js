import React, { useState, useEffect } from "react";
import { useForm } from "react-hook-form";
import Alert from "./Alert";

const UpdateTaskForm = ({ taskId, fetchTasks }) => {
  const [alertMessage, setAlertMessage] = useState("");
  const [alertType, setAlertType] = useState("success");
  const [showAlert, setShowAlert] = useState(false);

  const {
    register,
    handleSubmit,
    setValue,
    watch,
    reset,
    formState: { errors },
  } = useForm();

  useEffect(() => {
    const fetchSingleTask = async () => {
      let url = makWPtmData.restRoot + `wptm/v1/get-task/${taskId}`;

      try {
        const response = await fetch(url , {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            "X-WP-Nonce": makWPtmData?.nonce,
            // Include any additional headers if needed
          },
        });
        
        if (response.ok) {
          const result = await response.json();
          setValue("taskTitle", result?.title);
          setValue("taskDescription", result?.description);
          setValue("taskDuration", result?.duration);
          setValue("taskStatus", result?.status);
        } else {
          console.error("Failed to fetch task:", response.statusText);
        }
      } catch (error) {
        console.error("Error:", error);
      }
    };

    fetchSingleTask();
  }, [taskId]);

  const onSubmit = async (data) => {
    let formData = {
      title: data?.taskTitle,
      description: data?.taskDescription,
      duration: data?.taskDuration,
      status: data?.taskStatus,
    };

    let url = makWPtmData.restRoot + `wptm/v1/update-task/${taskId}`;

    try {
      const response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-WP-Nonce": makWPtmData?.nonce,
          // Include any additional headers if needed
        },
        body: JSON.stringify(formData),
      });

      if (response.ok) {
        const result = await response.json();
        console.log(result);
        if (result.success) {
          setAlertType("success");
          setShowAlert(true);
          setAlertMessage(result.message);
          fetchTasks();
        }
      } else {
        const error = await response.json();
        console.error(error);
        setAlertType("danger");
        setShowAlert(true);
        setAlertMessage("Something went wrong!");
      }
    } catch (error) {
      console.error("Error:", error);
      setAlertType("danger");
      setShowAlert(true);
      setAlertMessage("Something went wrong!");
    }
  };

  return (
    <div className="taskForm-wrapper mb-5">
      {showAlert && <Alert message={alertMessage} type={alertType}></Alert>}

      <form className="taskForm" onSubmit={handleSubmit(onSubmit)}>
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
            rows="2"
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
            {...register("taskDuration")}
          />
        </div>

        <div className="mb-3">
          <label for="taskStatus" className="form-label">
            Status
          </label>
          <select className="form-select" {...register("taskStatus")}>
            <option value="pending">Pending</option>
            <option value="in-progress">In Progress</option>
            <option value="completed">Completed</option>
          </select>
        </div>
        <input type="submit" value="Update Task" className="btn btn-primary" />
      </form>
    </div>
  );
};

export default UpdateTaskForm;
