import React from "react";

const TaskList = () => {
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
          <tr>
            <td>Mark</td>
            <td>Mark desc</td>
            <td>20 min</td>
            <td>pending</td>
            <td>
              <button class="btn btn-outline-primary">Edit</button>
              <button class="btn btn-outline-danger ms-2">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
      </>
  );
};

export default TaskList;
