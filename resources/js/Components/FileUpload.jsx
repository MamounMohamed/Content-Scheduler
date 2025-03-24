import React from "react";

const FileUpload = ({ label, onChange, accept }) => {
    return (
        <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {label}
            </label>
            <input
                type="file"
                onChange={onChange}
                accept={accept}
                className="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200"
            />
        </div>
    );
};

export default FileUpload;