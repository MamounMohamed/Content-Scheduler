import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import React, { useState } from "react";
import { Calendar, momentLocalizer } from "react-big-calendar";
import moment from "moment";
import "react-big-calendar/lib/css/react-big-calendar.css";

const localizer = momentLocalizer(moment);

const Dashboard = (props) => {
    const [view, setView] = useState("calendar"); // 'calendar' or 'list'
    const [filterStatus, setFilterStatus] = useState("all"); // 'all', 'scheduled', 'published', 'error'
    const [filterDate, setFilterDate] = useState(new Date('2022-01-01')); // Date
    const posts = props.posts;
    const filteredPosts = posts.filter((post) =>
        filterStatus === "all" ? true :
        post.status === filterStatus
    );

    console.log(filteredPosts);

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Dashboard
                </h2>
            }>
            <Head title="Dashboard" />

            
            <div className="p-4">
                <h1 className="text-2xl font-bold mb-4">Dashboard</h1>

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

                {/* Filter by Date */}
                {/* <div className="mb-4">
                    <label className="mr-2">Filter by Date:</label>
                    <DatePicker
                        selected={filterDate}
                        onChange={(date) => setFilterDate(date)}
                        showTimeSelect
                        timeFormat="HH:mm"
                        timeIntervals={15}
                        dateFormat="MMMM d, yyyy h:mm aa"
                        className="p-2 border rounded"
                    />
                </div> */}

                {/* Calendar View */}
                {view === "calendar" && (
                    <Calendar
                        localizer={localizer}
                        events={filteredPosts.map((post) => ({
                            title: post.title,
                            start: post.date,
                            end: post.date,
                        }))}
                        startAccessor="start"
                        endAccessor="end"
                        style={{ height: 500 }}
                    />
                )}

                {/* List View */}
                {view === "list" && (
                    <ul className="space-y-2">
                        {filteredPosts.map((post) => (
                            <li key={post.id} className="p-2 border rounded">
                                <div className="font-bold">{post.title}</div>
                                <div>{post.date}</div>
                                <div>{post.time}</div>
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
                            </li>
                        ))}
                    </ul>
                )}
            </div>
        </AuthenticatedLayout>
    );
};

export default Dashboard;

