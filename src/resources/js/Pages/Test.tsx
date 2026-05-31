import { useState } from 'react';

export default function Test() {
    const [count, setCount] = useState(0);

    return (
        <div className='flex min-h-screen items-center justify-center bg-gray-100'>
            <div className='rounded-xl bg-white p-6 text-center shadow'>
                <h1 className='text-xl font-bold text-blue-600'>Test Page</h1>

                <button
                    onClick={() => setCount(count + 1)}
                    className='mt-4 rounded bg-blue-500 px-4 py-2 text-white'
                >
                    Count: {count}
                </button>
            </div>
        </div>
    );
}
