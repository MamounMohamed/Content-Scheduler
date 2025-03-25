import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import React from "react";
import ErrorBox from "@/Components/ErrorBox"; // Reusable error message box
import "react-toastify/dist/ReactToastify.css";

const ViewPost = ({ postData }) => {
    const { title, content, image_url, platforms, scheduled_time } = postData;

    // Format the scheduled time for display
    const formattedDate = Date(scheduled_time);

    return (
        <AuthenticatedLayout>
            <Head title="View Post" />
            <div className="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                {/* Header */}
                <h1 className="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">
                    View Post
                </h1>

                {/* Post Title */}
                <div className="mb-4">
                    <h2 className="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        Title: {title || "No title available"}
                    </h2>
                </div>

                {/* Post Content */}
                <div className="mb-4">
                    <p className="text-gray-700 dark:text-gray-300">{content || "No content available"}</p>
                </div>

                {/* Platforms */}
                <div className="mb-4">
                    <h3 className="text-lg font-medium text-gray-800 dark:text-gray-200">
                        Platforms:
                    </h3>
                    <ul className="list-disc list-inside text-gray-600 dark:text-gray-400">
                        {platforms?.length > 0 ? (
                            platforms.map((platform) => (
                                <li key={platform.id}>{platform.name}</li>
                            ))
                        ) : (
                            <li>No platforms selected</li>
                        )}
                    </ul>
                </div>

                {/* Scheduled Time */}
                <div className="mb-4">
                    <h3 className="text-lg font-medium text-gray-800 dark:text-gray-200">
                        Scheduled Time:
                    </h3>
                    <p className="text-gray-700 dark:text-gray-300">
                        {formattedDate || "Not scheduled"}
                    </p>
                </div>

                {/* Image Preview */}
                {image_url && (
                    <div className="mb-4">
                        <h3 className="text-lg font-medium text-gray-800 dark:text-gray-200">
                            Image Preview:
                        </h3>
                        <img
                            src={image_url}
                            alt="Post preview"
                            className="w-full h-64 object-cover rounded-lg shadow-md"
                        />
                    </div>
                )}

                {/* Error Handling (if needed) */}
                {!postData && (
                    <ErrorBox message="The requested post could not be found." />
                )}
            </div>
        </AuthenticatedLayout>
    );
};

export default ViewPost;