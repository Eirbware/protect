console.log("HelloWorld!");
export default {
    login: () => "salut",
    getData: async () => {
        if (Math.random() > 0.5)
            throw new Error("dummy error");

        return { message: "empty" };
    },
    logout: () => "salut",
};
