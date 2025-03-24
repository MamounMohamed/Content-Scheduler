import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import React, { useState } from "react";
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";

const PostEditor = (props) => {
    
    const { postData, allPlatforms } = props;
    console.log(postData);
    console.log(allPlatforms);

    const [title, setTitle] = useState(postData?.title || "");
    const [content, setContent] = useState(postData?.content || "");
    const [image, setImage] = useState(postData?.image_url || null);
    const [platforms, setPlatforms] = useState(postData?.platforms || []);
    const [scheduleDate, setScheduleDate] = useState(postData?.scheduled_time || new Date());
    const [error, setError] = useState("");

    const handleSubmit = (e) => {
        e.preventDefault();
        if (!title || !content || platforms.length === 0) {
            setError("Please fill out all required fields.");
            return;
        }
        setError("");
        console.log("Submitting:", { title, content, image, platforms, scheduleDate });
    };

    return (
        <AuthenticatedLayout>
            <Head
                header={
                    <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                        PostEditor
                    </h2>
                }
            />
            <div className="p-4">
                <h1 className="text-2xl font-bold mb-4">Post Editor</h1>
                <form onSubmit={handleSubmit} className="space-y-4">
                    {/* Title Input */}
                    <div>
                        <label className="block text-sm font-medium mb-1">Title</label>
                        <input
                            type="text"
                            value={title}
                            onChange={(e) => setTitle(e.target.value)}
                            className="w-full p-2 border rounded"
                            placeholder="Enter post title"
                            maxLength={100}
                            required
                        />
                    </div>

                    {/* Content Textarea */}
                    <div>
                        <label className="block text-sm font-medium mb-1">Content</label>
                        <textarea
                            value={content}
                            onChange={(e) => setContent(e.target.value)}
                            className="w-full p-2 border rounded"
                            placeholder="Enter post content"
                            maxLength={500}
                            rows={4}
                            required
                        />
                        <div className="text-sm text-gray-500">{content.length}/500 characters</div>
                    </div>

                    {/* Platform Selector */}
                    <div>
                        <label className="block text-sm font-medium mb-1">Platforms</label>
                        <select
                            multiple
                            value={platforms}
                            onChange={(e) =>
                                setPlatforms([...e.target.selectedOptions].map((opt) => opt.value))
                            }
                            className="w-full p-2 border rounded"
                            required
                        >
                            {allPlatforms?.map((platform) => (
                                <option key={platform.id} value={platform.id}>
                                    {platform.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    {/* Date/Time Picker */}
                    <div>
                        <label className="block text-sm font-medium mb-1">Schedule Date</label>
                        <DatePicker
                            selected={scheduleDate}
                            onChange={(date) => setScheduleDate(date)}
                            showTimeSelect
                            timeFormat="HH:mm"
                            timeIntervals={15}
                            dateFormat="MMMM d, yyyy h:mm aa"
                            className="w-full p-2 border rounded"
                        />
                    </div>

                    {/* Image Upload */}
                    <div>
                        <label className="block text-sm font-medium mb-1">Upload Image</label>
                        <input
                            type="file"
                            onChange={(e) => setImage(e.target.files[0])}
                            className="w-full p-2 border rounded"
                            accept="image/*"
                        />
                    </div>

                    {/* Error Message */}
                    {error && <div className="text-red-500 text-sm">{error}</div>}

                    {/* Submit Button */}
                    <button
                        type="submit"
                        className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                    >
                        Schedule Post
                    </button>
                </form>
            </div>
        </AuthenticatedLayout>
    );
};

export default PostEditor;