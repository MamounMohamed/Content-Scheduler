import React from "react";

const TextArea = ({ label, value, onChange, placeholder, maxLength, rows, required }) => {
    return (
        <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {label}
            </label>
            <textarea
                value={value}
                onChange={onChange}
                placeholder={placeholder}
                maxLength={maxLength}
                rows={rows}
                required={required}
                className="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200"
            />
            <div className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {value.length}/{maxLength} characters
            </div>
        </div>
    );
};

export default TextArea;