import React from "react";

const Unauthorized = () => {
    return (
        <div className="flex items-center justify-center h-screen bg-gray-100 dark:bg-gray-900">
            <div className="text-center">
                <h1 className="text-6xl font-bold text-red-500">403</h1>
                <p className="mt-4 text-2xl font-medium text-gray-800 dark:text-gray-200">
                    Unauthorized Access
                </p>
                <p className="mt-2 text-gray-600 dark:text-gray-400">
                    You do not have permission to view this page.
                </p>
            </div>
        </div>
    );
};

export default Unauthorized;