import React from 'react';

const Paginator = ({ currentPage, lastPage, onPageChange }) => {
    const pages = Array.from({ length: lastPage }, (_, i) => i + 1);

    return (
        <div className="paginator flex justify-center mt-4 space-x-2">
            {pages.map((page) => (
                <button
                    key={page}
                    onClick={() => onPageChange(page)}
                    disabled={page === currentPage}
                    className={`px-3 py-1 rounded ${
                        page === currentPage ? "bg-blue-500 text-white" : "bg-gray-200"
                    }`}
                >
                    {page}
                </button>
            ))}
        </div>
    );
};

export default Paginator;