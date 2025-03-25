import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import React, { useState } from "react";
import { Calendar, momentLocalizer } from "react-big-calendar";
import moment from "moment";
import "react-big-calendar/lib/css/react-big-calendar.css";
import { PencilLine, Trash , CirclePlus } from 'lucide-react';
import axios from 'axios';

const localizer = momentLocalizer(moment);

const Posts = (props) => {
    const [view, setView] = useState("calendar"); // 'calendar' or 'list'
    const [filterStatus, setFilterStatus] = useState("all"); // 'all', 'scheduled', 'published', 'error'
    const [posts, setPosts] = useState(props.posts);
    const filteredPosts = posts.filter((post) =>
        filterStatus === "all" ? true :
            post.status === filterStatus
    );
    function onDelete(id) {
        axios.delete(route('posts.destroy', { id: id }))
            .then(function (response) {
                console.log(response);
            })
            .catch(function (error) {
                console.log(error);
            });
        posts.splice(posts.findIndex(post => post.id === id), 1);
        setPosts([...posts]);
    }

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Posts
                </h2>
            }>
            <Head title="Posts" />




            <div className="p-4">
                <h1 className="text-2xl font-bold mb-4">Your Posts</h1>
                <div className='flex row justify-between items-center'>
                    {/* View Toggle */}
                    <div className="flex space-x-4 mb-4">
                        <button
                            onClick={() => setView("calendar")}
                            className={`px-4 py-2 rounded ${view === "calendar" ? "bg-blue-500 text-white" : "bg-gray-200"
                                }`}
                        >
                            Calendar View
                        </button>
                        <button
                            onClick={() => setView("list")}
                            className={`px-4 py-2 rounded ${view === "list" ? "bg-blue-500 text-white" : "bg-gray-200"
                                }`}
                        >
                            List View
                        </button>
                    </div>

                    <a href={route('posts.create')} className='flex flex-row items-center space-x-2 rounded-md px-4 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white justify-end gap-2'>
                       Create Post<CirclePlus />
                    </a>
                </div>

                {/* Filters */}
                <div className="mb-4">
                    <label className="mr-2">Filter by Status:</label>
                    <select
                        value={filterStatus}
                        onChange={(e) => setFilterStatus(e.target.value)}
                        className="p-2 border rounded"
                    >
                        <option value="all">All</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>


                {/* Calendar View */}
                {view === "calendar" && (
                    <Calendar
                        localizer={localizer}
                        views={['month', 'agenda']} // Only show month and agenda views

                        events={
                            filteredPosts.map((post) => ({
                                title: post.title,
                                start: Date.parse(post.scheduled_time),
                                end: Date.parse(post.scheduled_time),
                            }))
                        }
                        startAccessor="start"
                        endAccessor="end"
                        style={{ height: 500 }}
                    />
                )}

                {/* List View */}
                {view === "list" && (
                    <ul className="space-y-2">
                        {filteredPosts.map((post) => (
                            <li key={post.id} className="p-2 border rounded grid xl:grid-cols-5 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                <div className="flex flex-col">

                                    <div className="font-bold">{post.title}</div>
                                    <div
                                        className={`text-sm ${post.status === "scheduled"
                                            ? "text-blue-500"
                                            : post.status === "published"
                                                ? "text-green-500"
                                                : "text-red-500"
                                            }`}
                                    >
                                        {post.status}
                                    </div>
                                </div>

                                <div>{post.date}</div>
                                <div>{post.time}</div>
                                <div className='truncate'>{post.content}</div>

                                <div className="flex space-x-2">
                                    {
                                        post.status !== "published" &&
                                        <div className='flex flex-row items-center space-x-2'>
                                            <a href={route('posts.edit', { id: post.id })}>
                                                <PencilLine className='cursor-pointer' />
                                            </a>
                                            <Trash onClick={() => onDelete(post.id)} className='cursor-pointer' />
                                        </div>
                                    }

                                </div>
                            </li>
                        ))}
                    </ul>
                )}
            </div>
        </AuthenticatedLayout>
    );
};

export default Posts;

