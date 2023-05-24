const apiUrl = process.env.API_URL ?? 'https://rashin.me';

// @ts-ignore
export const authProvider = {
    login: ({ username, password }: {username: string, password: string}) => {
        const request = new Request(`${apiUrl}/login`, {
            method: 'POST',
            body: JSON.stringify({ email: username, password }),
            headers: new Headers({ 'Content-Type': 'application/json' }),
        });

        return fetch(request)
            .then(response => response.json())
            .then(response => {
                const isSuccess = response.code === 200;

                if (!isSuccess) {
                    return Promise.reject(response.error);
                }

                const request = new Request(`${apiUrl}/user`, {
                    method: 'GET',
                    headers: new Headers({ 'Content-Type': 'application/json' }),
                });

                return fetch(request)
                    .then(response => response.json())
                    .then(response => {
                        localStorage.setItem('auth', JSON.stringify(response));
                    })
                    .catch(res => {
                        return Promise.reject(res);
                    });
            });
    },
    logout: () => {
        localStorage.removeItem('auth');
        return Promise.resolve();
    },
    checkAuth: () =>
        localStorage.getItem('auth') ? Promise.resolve() : Promise.reject(),
    checkError:  (error: any) => {
        const status = error.status;
        if (status === 401 || status === 403) {
            localStorage.removeItem('auth');
            return Promise.reject();
        }

        return Promise.resolve();
    },
    getIdentity: () => {
        try {
            const value = localStorage?.getItem('auth')??'';
            const { id, firstName, lastName } = JSON.parse(value);
            const fullName = `${firstName} ${lastName}`;
            return Promise.resolve({ id, fullName });
        } catch (error) {
            return Promise.reject(error);
        }
        // Promise.resolve({
        //     id: 'user',
        //     fullName: 'John Doe',
        // })
    },
    getPermissions: () => Promise.resolve(''),
};
