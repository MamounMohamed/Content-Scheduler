import React from "react";

const NotFound = () => {
    return (
        <div className="flex items-center justify-center h-screen bg-gray-100 dark:bg-gray-900">
            <div className="text-center">   
                <h1 className="text-6xl font-bold text-red-500">404</h1>
                <p className="mt-4 text-2xl font-medium text-gray-800 dark:text-gray-200">
                    Page Not Found
                </p>
                <p className="mt-2 text-gray-600 dark:text-gray-400">
                    The page you are looking for could not be found.
                </p>
            </div>
        </div>
    );
};

export default NotFound;