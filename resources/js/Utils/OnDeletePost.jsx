export default function OnDeletePost(id) {
    axios.delete(route('posts.destroy', { id }))
        .then(() => {
            // Remove the deleted post from the current state
            setPosts(posts.filter(post => post.id !== id));
            toast.success('Post deleted successfully');
        })
        .catch((error) => {
            toast.error(error?.response?.data?.message);
            console.error(error);
        });
};