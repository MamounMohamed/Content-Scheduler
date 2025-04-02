import React from 'react';

const ConfirmDeleteModal = ({ onConfirm, onCancel, title, message }) => {
    console.log("ConfirmDeleteModal rendered");

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div className="bg-white rounded-lg p-6 shadow-lg w-full max-w-md">
                {/* Modal Header */}
                <h2 className="text-xl font-semibold text-gray-800 mb-4">{title}</h2>

                {/* Modal Body */}
                <p className="text-gray-700 mb-6">{message}</p>

                {/* Modal Actions */}
                <div className="flex justify-end space-x-4">
                    <button
                        onClick={onCancel}
                        className="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300 focus:outline-none"
                    >
                        Cancel
                    </button>
                    <button
                        onClick={onConfirm}
                        className="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700 focus:outline-none"
                    >
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    );
};

export default ConfirmDeleteModal;