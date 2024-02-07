import React, { useState } from 'react';
import TaskList from './components/TaskList';
import TaskForm from './components/TaskForm';

const App = () => {

    const [showForm, setShowForm] = useState(true);

    return (
        <div>
            <h2 className='app-title'>Task Manager</h2>
            <hr />

            <h4>All Tasks <button class="btn btn-outline-primary ms-2" onClick={() => setShowForm(true)}>Create New Task</button></h4>
            
            {showForm && <TaskForm></TaskForm>}

            <TaskList></TaskList>

        </div>
     );
}

export default App; 