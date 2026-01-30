import React from "react";
import { motion } from "framer-motion";

export default function AboutHero() {
  return (
    <section className="relative h-[50vh] min-h-[400px] overflow-hidden">
      <div 
        className="absolute inset-0 bg-cover bg-center"
        style={{ 
          backgroundImage: "url(https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1920&q=80)"
        }}
      />
      <div className="absolute inset-0 bg-gradient-to-r from-[#1e3a5f]/95 via-[#1e3a5f]/80 to-[#1e3a5f]/60" />
      
      <div className="relative z-10 h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-center">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          className="text-center"
        >
          <motion.div 
            initial={{ width: 0 }}
            animate={{ width: "80px" }}
            transition={{ duration: 0.5, delay: 0.3 }}
            className="h-1 bg-[#f5a623] mx-auto mb-6"
          />
          <h1 className="text-5xl md:text-7xl font-bold text-white mb-4">
            About <span className="text-[#f5a623]">RETI</span>
          </h1>
          <p className="text-xl text-gray-300 max-w-2xl mx-auto">
            Empowering Zambia's workforce through skills development
          </p>
        </motion.div>
      </div>
    </section>
  );
}