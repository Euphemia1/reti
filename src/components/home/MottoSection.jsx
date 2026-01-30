import React from "react";
import { motion } from "framer-motion";
import { Target, Users, Award, Briefcase } from "lucide-react";

export default function MottoSection() {
  const stats = [
    { icon: Users, value: "410+", label: "Current Students", color: "bg-blue-500" },
    { icon: Award, value: "66", label: "Certified Teachers", color: "bg-green-500" },
    { icon: Briefcase, value: "21+", label: "Approved Courses", color: "bg-purple-500" },
    { icon: Target, value: "1913+", label: "Graduate Students", color: "bg-orange-500" },
  ];

  return (
    <section className="py-20 bg-gradient-to-br from-[#1e3a5f] via-[#2d5a8a] to-[#1e3a5f] relative overflow-hidden">
      {/* Background Pattern */}
      <div className="absolute inset-0 opacity-10">
        <div className="absolute top-0 left-0 w-96 h-96 bg-[#f5a623] rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2" />
        <div className="absolute bottom-0 right-0 w-96 h-96 bg-[#f5a623] rounded-full blur-3xl translate-x-1/2 translate-y-1/2" />
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-16"
        >
          <span className="text-[#f5a623] font-semibold text-sm tracking-widest uppercase mb-4 block">
            Our Motto
          </span>
          <h2 className="text-3xl md:text-5xl font-bold text-white leading-tight max-w-4xl mx-auto">
            Striving to Reduce the Skills Gap in the{" "}
            <span className="text-[#f5a623]">Construction Industry</span>
          </h2>
          <div className="w-24 h-1 bg-[#f5a623] mx-auto mt-8 rounded-full" />
        </motion.div>

        {/* Stats Grid */}
        <div className="grid grid-cols-2 md:grid-cols-4 gap-6">
          {stats.map((stat, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.1 }}
              className="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center border border-white/20 hover:bg-white/20 transition-all duration-300 group"
            >
              <div className={`w-16 h-16 ${stat.color} rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-lg`}>
                <stat.icon className="w-8 h-8 text-white" />
              </div>
              <h3 className="text-4xl font-bold text-white mb-2">{stat.value}</h3>
              <p className="text-gray-300 font-medium">{stat.label}</p>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}