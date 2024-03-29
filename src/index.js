import App from "./App";
import { render } from '@wordpress/element';

/**
 * Import the stylesheet for the plugin.
 */
import './style/main.scss';

// Render the App component into the DOM
render(<App />, document.getElementById('wp_task_manager'));