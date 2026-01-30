import React from "react";
import { motion } from "framer-motion";
import { Eye, Rocket } from "lucide-react";

export default function VisionMission() {
  return (
    <section className="py-24 bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid md:grid-cols-2 gap-8">
          {/* Vision */}
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="bg-white rounded-3xl p-10 shadow-lg hover:shadow-xl transition-shadow"
          >
            <div className="w-20 h-20 bg-gradient-to-br from-[#1e3a5f] to-[#2d5a8a] rounded-3xl flex items-center justify-center mb-8 shadow-lg">
              <Eye className="w-10 h-10 text-[#f5a623]" />
            </div>
            <h3 className="text-3xl font-bold text-[#1e3a5f] mb-6">Our Vision</h3>
            <p className="text-gray-600 text-lg leading-relaxed">
              To reduce the skills gap in the construction industry by providing short courses in scarce skills that make learners work-ready for employment and entrepreneurship opportunities.
            </p>
          </motion.div>

          {/* Mission */}
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ delay: 0.1 }}
            className="bg-gradient-to-br from-[#1e3a5f] to-[#2d5a8a] rounded-3xl p-10 shadow-lg hover:shadow-xl transition-shadow"
          >
            <div className="w-20 h-20 bg-white/10 rounded-3xl flex items-center justify-center mb-8 border border-white/20">
              <Rocket className="w-10 h-10 text-[#f5a623]" />
            </div>
            <h3 className="text-3xl font-bold text-white mb-6">Our Mission</h3>
            <p className="text-gray-300 text-lg leading-relaxed">
              To provide the highest standard of training and meet the demand of the skills using innovation and technological approaches in line with the world best practices.
            </p>
          </motion.div>
        </div>
      </div>
    </section>
  );
}