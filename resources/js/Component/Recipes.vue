<template>
    <div class="main-container">
        <div class="top-button-container">
            <button>
                All Recipe
            </button>
            <button>
                Premium
            </button>
            <button>
                Free
            </button>
            <button>
                Most Liked
            </button>
        </div>

    </div>
</template>

<style scoped>
    .main-container {
        width: 100%;
        height: auto;
        display: flex;
    }
    .top-button-container {
        width: 100%;
        height: 100px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }
    .top-button-container button {
        width: 120px;
        height: 40px;
        background-color: #435F77;
        border: none;
        border-radius: 10px;
        color: white;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-style: italic;
    }
</style>
