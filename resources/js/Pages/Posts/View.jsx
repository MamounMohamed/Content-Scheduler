import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import React , {useState} from "react";
import ErrorBox from "@/Components/ErrorBox"; // Reusable error message box
import "react-toastify/dist/ReactToastify.css";
import { List, PencilLine, Plus, PlusCircle, Trash } from "lucide-react";
import OnDeletePost from "@/Utils/OnDeletePost";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal";
const ViewPost = ({ postData }) => {
    const { title, content, image_url, platforms, scheduled_time } = postData;
    const [confirmDelete, setConfirmDelete] = useState(false);


    // Format the scheduled time for display
    const formattedDate = Date(scheduled_time);

    return (
        <AuthenticatedLayout>
            <Head title="View Post" />
            <div className="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                {/* Header */}
                <div className="flex justify-between items-center mb-4 flex-row flex-wrap">
                    <h1 className="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">
                        View Post
                    </h1>

                    {/* Edit , Delete , View , List Actions */}
                    <div className="flex justify-between items-center mb-4">
                        <div className="flex space-x-4">
                            <a href={route("posts.create")}>
                                <button className="px-4 py-2 rounded bg-green-500 text-white flex flex-row items-center space-x-2">
                                    Create <PlusCircle className="ml-2" />
                                </button>
                            </a>
                            <a href={route("posts.edit", { post: postData.id })}>
                                <button className="px-4 py-2 rounded bg-blue-500 text-white flex flex-row items-center space-x-2">
                                    Edit <PencilLine className="ml-2" />
                                </button>
                            </a>
                            
                            <button className="px-4 py-2 rounded bg-red-500 text-white flex flex-row items-center space-x-2" onClick={() => setConfirmDelete(true)}>
                                Delete <Trash className="ml-2" />
                            </button>
                            <a href={route("posts.index")}>
                                <button className="px-4 py-2 rounded bg-yellow-500 text-white flex flex-row items-center space-x-2">
                                    List <List className="ml-2" />
                                </button>
                            </a>
                        </div>
                    </div>
                </div>

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
                            className="object-cover rounded-lg shadow-md"
                        />
                    </div>
                )}

                {/* Error Handling (if needed) */}
                {!postData && (
                    <ErrorBox message="The requested post could not be found." />
                )}
            </div>

            {/* Confirm Delete Modal */}
            {confirmDelete && (
                <ConfirmDeleteModal
                onConfirm={() => {
                    OnDeletePost(postData.id);
                    setConfirmDelete(false);
                    toast.success('Post deleted successfully');
                    window.setTimeout(() => {
                        window.location.href = route("posts.index");
                    }, 3000 );
                }}
                onCancel={() => setConfirmDelete(false)}
                title="Delete Post"
                message={`Are you sure you want to delete the post "${postData.title}"?`}
            />
            )}

            {/* Toast Notifications Container */}
            <ToastContainer
                position="top-right"
                autoClose={3000}
                hideProgressBar={false}
                newestOnTop={false}
                closeOnClick
                rtl={false}
                pauseOnFocusLoss
                draggable
                pauseOnHover
            />
        </AuthenticatedLayout>
    );
};

export default ViewPost;