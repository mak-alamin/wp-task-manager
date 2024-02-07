import React, { useState } from 'react';
import TaskList from './components/TaskList';
import TaskForm from './components/TaskForm';

const App = () => {

    const [showForm, setShowForm] = useState(false);

    return (
        <div>
            <h2 className='app-title text-success'>Task Manager</h2>
            <hr />

            <button class="btn btn-outline-primary mb-3" onClick={() => setShowForm(true)}>Create New Task</button>
            
            {showForm && <TaskForm></TaskForm>}

            <TaskList></TaskList>

        </div>
     );
}

export default App; 