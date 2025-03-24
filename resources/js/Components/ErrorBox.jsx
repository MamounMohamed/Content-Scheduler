import React from "react";

const ErrorBox = ({ message }) => {
    return (
        <div className="text-red-500 text-sm bg-red-100 dark:bg-red-900 dark:text-red-300 p-3 rounded-md">
            {message}
        </div>
    );
};

export default ErrorBox;