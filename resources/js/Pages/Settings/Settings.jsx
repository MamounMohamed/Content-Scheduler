import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import React, { useState } from "react";

const Settings = (allPlatforms) => {
    const [platforms, setPlatforms] = useState(allPlatforms);

    const handleAddPlatform = (e) => {
        e.preventDefault();
        const newPlatform = e.target.elements.platform.value.trim();
        if (newPlatform && !platforms.includes(newPlatform)) {
            setPlatforms([...platforms, newPlatform]);
            e.target.reset();
        }
    };

    const handleRemovePlatform = (platform) => {
        setPlatforms(platforms.filter((p) => p !== platform));
    };

    return (
        <AuthenticatedLayout>
            <Head header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Settings
                </h2>
            } />
            <div className="p-4">
                <h1 className="text-2xl font-bold mb-4">Settings</h1>

                {/* Add Platform Form */}
                <form onSubmit={handleAddPlatform} className="mb-4">
                    <input
                        type="text"
                        name="platform"
                        placeholder="Add a new platform"
                        className="p-2 border rounded mr-2"
                        required
                    />
                    <button
                        type="submit"
                        className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                    >
                        Add
                    </button>
                </form>

                {/* Platform List */}
                <ul className="space-y-2">
                    {platforms.map((platform) => (
                        <li key={platform} className="flex justify-between items-center p-2 border rounded">
                            <span>{platform}</span>
                            <button
                                onClick={() => handleRemovePlatform(platform)}
                                className="text-red-500 hover:text-red-700"
                            >
                                Remove
                            </button>
                        </li>
                    ))}
                </ul>
            </div>
        </AuthenticatedLayout>

    );
};

export default Settings;