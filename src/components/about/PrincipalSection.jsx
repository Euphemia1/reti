import React from "react";
import { motion } from "framer-motion";
import { Quote } from "lucide-react";

export default function PrincipalSection() {
  return (
    <section className="py-24 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-12"
        >
          <span className="text-[#f5a623] font-semibold text-sm tracking-widest uppercase mb-4 block">
            Leadership
          </span>
          <h2 className="text-4xl md:text-5xl font-bold text-[#1e3a5f]">
            Meet Our Principal
          </h2>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="max-w-4xl mx-auto"
        >
          <div className="bg-gradient-to-br from-gray-50 to-white rounded-3xl p-8 md:p-12 shadow-lg">
            <div className="flex flex-col md:flex-row items-center gap-8">
              {/* Image */}
              <div className="relative">
                <div className="w-48 h-48 md:w-56 md:h-56 rounded-3xl overflow-hidden shadow-xl">
                  <img
                    src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&q=80"
                    alt="Dr. Peggy Nsama Tembo"
                    className="w-full h-full object-cover"
                  />
                </div>
                <div className="absolute -bottom-4 -right-4 w-16 h-16 bg-[#f5a623] rounded-2xl flex items-center justify-center shadow-lg">
                  <Quote className="w-8 h-8 text-white" />
                </div>
              </div>

              {/* Content */}
              <div className="flex-grow text-center md:text-left">
                <h3 className="text-2xl md:text-3xl font-bold text-[#1e3a5f] mb-2">
                  Dr. Peggy Nsama Tembo
                </h3>
                <p className="text-[#f5a623] font-semibold text-lg mb-6">Principal</p>
                <blockquote className="text-gray-600 text-lg leading-relaxed italic">
                  "At RETI, we are committed to transforming lives through quality vocational education. Our goal is to produce graduates who are not just certified, but truly skilled and ready to make a meaningful contribution to Zambia's development."
                </blockquote>
              </div>
            </div>
          </div>
        </motion.div>
      </div>
    </section>
  );
}