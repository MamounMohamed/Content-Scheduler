import React from "react";

const MultiSelect = ({ label, options, value, onChange, required }) => {
    return (
        <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {label}
            </label>
            <select
                multiple
                value={value}
                onChange={(e) => onChange([...e.target.selectedOptions].map((opt) => opt.value))}
                className="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200"
                required={required}
            >
                {options.map((option) => (
                    <option key={option.value} value={option.value} className="p-2 border rounded">
                        {option.label}
                    </option>
                ))}
            </select>
        </div>
    );
};

export default MultiSelect;