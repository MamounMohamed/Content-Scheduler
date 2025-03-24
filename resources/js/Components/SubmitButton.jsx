import React from "react";

const SubmitButton = ({ label }) => {
    return (
        <button
            type="submit"
            className="w-full bg-blue-500 text-white px-4 py-3 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
        >
            {label}
        </button>
    );
};

export default SubmitButton;