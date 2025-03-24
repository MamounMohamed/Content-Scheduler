import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import React, { useState } from "react";
import TextInput from "@/Components/TextInput"; // Reusable text input
import TextArea from "@/Components/TextArea"; // Reusable textarea
import MultiSelect from "@/Components/MultiSelect"; // Reusable multi-select dropdown
import DateTimePicker from "@/Components/DateTimePicker"; // Reusable date/time picker
import FileUpload from "@/Components/FileUpload"; // Reusable file upload
import ErrorBox from "@/Components/ErrorBox"; // Reusable error message box
import SubmitButton from "@/Components/SubmitButton"; // Reusable submit button
import axios from 'axios';
const PostEditor = (props) => {
    const { postData, allPlatforms, mode } = props;

    const [title, setTitle] = useState(postData?.title || "");
    const [content, setContent] = useState(postData?.content || "");
    const [image, setImage] = useState(null);
    const [platforms, setPlatforms] = useState(postData?.platforms || []);
    const [scheduleDate, setScheduleDate] = useState(new Date(postData?.scheduled_time || new Date()));
    const [error, setError] = useState("");

    const handleSubmit = (e) => {
        e.preventDefault();
        if (!title || !content || platforms.length === 0) {
            setError("Please fill out all required fields.");
            return;
        }
        const formData = new FormData();
        formData.append('title', title);
        formData.append('content', content);
        formData.append('platforms', platforms);
        formData.append('scheduled_time', scheduleDate);
        if (image) 
            formData.append("image", image);

        const config = {
            headers: { 'Content-Type': 'multipart/form-data' }
        }
        if (mode === 'edit') {
            axios.put(route('posts.update', { post: postData.id }), formData, config)
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
        } else {
            axios.post(route('posts.store'), formData, config)
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
        setError("");
        console.log("Submitting:", { title, content, image, platforms, scheduleDate });
    };

    return (
        <AuthenticatedLayout>
            <Head title="Post Editor" />
            <div className="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <h1 className="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Post Editor</h1>
                <form onSubmit={handleSubmit} className="space-y-6">
                    {/* Title Input */}
                    <TextInput
                        label="Title"
                        value={title}
                        onChange={(e) => setTitle(e.target.value)}
                        placeholder="Enter post title"
                        maxLength={100}
                        required
                    />

                    {/* Content Textarea */}
                    <TextArea
                        label="Content"
                        value={content}
                        onChange={(e) => setContent(e.target.value)}
                        placeholder="Enter post content"
                        maxLength={500}
                        rows={4}
                        required
                    />

                    {/* Platform Selector */}
                    <MultiSelect
                        label="Platforms"
                        options={allPlatforms?.map((platform) => ({
                            value: platform.id,
                            label: platform.name,
                        }))}
                        value={platforms}

                        onChange={(selected) => setPlatforms(selected)}
                        required
                    />

                    {/* Date/Time Picker */}
                    <DateTimePicker
                        label="Schedule Date"
                        selected={scheduleDate}
                        onChange={(date) => setScheduleDate(date)}
                    />

                    {/* Image Upload */}
                    <FileUpload
                        label="Upload Image"
                        onChange={(e) => setImage(e.target.files[0])}
                        accept="image/*"
                    />

                    {/* Error Message */}
                    {error && <ErrorBox message={error} />}

                    {/* Submit Button */}
                    <SubmitButton label="Schedule Post" />
                </form>
            </div>
        </AuthenticatedLayout>
    );
};

export default PostEditor;