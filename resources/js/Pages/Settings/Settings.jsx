import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import React, { useState } from "react";
import axios from "axios";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import Paginator from "@/Components/Paginator";

const Settings = ({ platforms }) => {
    // State to store platforms and trigger re-renders
    const [platformList, setPlatformList] = useState(platforms);
    const [currentPage, setCurrentPage] = useState(1); // Current page for client-side pagination
    const [itemsPerPage] = useState(10); // Number of items per page

    // Calculate paginated platforms
    const indexOfLastPlatform = currentPage * itemsPerPage;
    const indexOfFirstPlatform = indexOfLastPlatform - itemsPerPage;
    const currentPlatforms = platformList.slice(indexOfFirstPlatform, indexOfLastPlatform);

    // Function to toggle the active status of a platform
    const handleToggleActive = async (platformId) => {
        try {
            const response = await axios.put(route('platforms.toggle-active', { id: platformId }));
            console.log(response);
            const updatedPlatform = response?.data?.data?.platform;

            // Update the platform list in state
            setPlatformList((prevPlatforms) =>
                prevPlatforms.map((platform) =>
                    platform.id === updatedPlatform.id
                        ? { ...platform, is_active: updatedPlatform.is_active }
                        : platform
                )
            );

            // Show success notification
            toast.success(
                updatedPlatform.is_active
                    ? `${updatedPlatform?.name} activated successfully!`
                    : `${updatedPlatform?.name} deactivated successfully!`
            );
        } catch (error) {
            // Show error notification
            toast.error(error?.response?.data?.data);
            console.error(error);
        }
    };

    // Handle page change
    const handlePageChange = (page) => {
        setCurrentPage(page);
    };

    return (
        <AuthenticatedLayout>
            <Head title="Settings" />
            <div className="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <h1 className="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">
                    Platform Settings
                </h1>

                {/* Platforms Table */}
                <div className="overflow-x-auto">
                    <table className="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead className="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    scope="col"
                                    className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                >
                                    Name
                                </th>
                                <th
                                    scope="col"
                                    className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                >
                                    Post
                                </th>
                                <th
                                    scope="col"
                                    className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                >
                                    Status
                                </th>
                                <th
                                    scope="col"
                                    className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody className="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            {currentPlatforms.length > 0 ? (
                                currentPlatforms.map((platform) => (
                                    <tr key={platform.id}>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                            {platform.name}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {platform.post_title}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {platform.is_active ? (
                                                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                    Active
                                                </span>
                                            ) : (
                                                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                    Inactive
                                                </span>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button
                                                onClick={() => handleToggleActive(platform.id)}
                                                className={`px-4 py-2 rounded ${
                                                    platform.is_active
                                                        ? "bg-red-500 hover:bg-red-600 text-white"
                                                        : "bg-green-500 hover:bg-green-600 text-white"
                                                }`}
                                            >
                                                {platform.is_active ? "Deactivate" : "Activate"}
                                            </button>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td
                                        colSpan="4"
                                        className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center"
                                    >
                                        No platforms found on this page.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                {/* Paginator */}
                <div className="mt-6 flex justify-center">
                    <Paginator
                        currentPage={currentPage}
                        lastPage={Math.ceil(platformList.length / itemsPerPage)}
                        onPageChange={handlePageChange}
                    />
                </div>

                {/* Toast Notifications Container */}
                <div className="mt-6">
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
                </div>
            </div>
        </AuthenticatedLayout>
    );
};

export default Settings;