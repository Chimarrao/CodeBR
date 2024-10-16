<template>
    <div class="column is-4">
        <div class="box skeleton-box">
            <article>
                <figure class="image is-3by2 skeleton-image"></figure>
                <div class="content">
                    <h3 class="title is-4 skeleton-title mt-2"></h3>
                    <p class="skeleton-text"></p>
                </div>
            </article>
        </div>
    </div>
</template>

<script>
export default {
    name: 'SkeletonLoader',
};
</script>

<style scoped>
.skeleton-box {
    border-radius: 4px;
    padding: 20px;
}

.skeleton-image {
    background-color: #e0e0e0;
    height: 0;
    padding-bottom: 66.66%;
    width: 100%;
    border-radius: 4px;
}

.skeleton-title {
    width: 60%;
    height: 20px;
    background-color: #e0e0e0;
    margin-bottom: 10px;
    border-radius: 4px;
}

.skeleton-text {
    width: 100%;
    height: 3rem;
    background-color: #e0e0e0;
    margin-bottom: 10px;
    border-radius: 4px;
}

</style>
