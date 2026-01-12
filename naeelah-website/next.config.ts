import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  // Explicitly set Turbopack root to silence warning about multiple lockfiles
  turbopack: {
    root: __dirname,
  },
};

export default nextConfig;
