import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { createPageUrl } from "@/utils";
import { Menu, X, Phone, Mail, MapPin, Facebook, Twitter, Instagram, Linkedin, GraduationCap, ChevronUp } from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";

export default function Layout({ children, currentPageName }) {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const [showScrollTop, setShowScrollTop] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 50);
      setShowScrollTop(window.scrollY > 500);
    };
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  const navLinks = [
    { name: "Home", page: "Home" },
    { name: "About Us", page: "About" },
    { name: "Courses", page: "Courses" },
    { name: "News", page: "News" },
    { name: "Contact", page: "Contact" },
  ];

  const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  return (
    <div className="min-h-screen flex flex-col bg-white">
      <style>{`
        :root {
          --reti-navy: #1e3a5f;
          --reti-navy-dark: #152a45;
          --reti-gold: #f5a623;
          --reti-gold-light: #ffc857;
          --reti-light-blue: #e8f4fc;
        }
      `}</style>

      {/* Top Bar */}
      <div className="bg-[#1e3a5f] text-white py-2 px-4 hidden md:block">
        <div className="max-w-7xl mx-auto flex justify-between items-center text-sm">
          <div className="flex items-center gap-6">
            <a href="tel:0954999900" className="flex items-center gap-2 hover:text-[#f5a623] transition-colors">
              <Phone className="w-4 h-4" />
              <span>0954-999900</span>
            </a>
            <a href="mailto:academic@reti.edu.zm" className="flex items-center gap-2 hover:text-[#f5a623] transition-colors">
              <Mail className="w-4 h-4" />
              <span>academic@reti.edu.zm</span>
            </a>
          </div>
          <div className="flex items-center gap-4">
            <a href="#" className="hover:text-[#f5a623] transition-colors"><Facebook className="w-4 h-4" /></a>
            <a href="#" className="hover:text-[#f5a623] transition-colors"><Twitter className="w-4 h-4" /></a>
            <a href="#" className="hover:text-[#f5a623] transition-colors"><Instagram className="w-4 h-4" /></a>
            <a href="#" className="hover:text-[#f5a623] transition-colors"><Linkedin className="w-4 h-4" /></a>
          </div>
        </div>
      </div>

      {/* Main Navigation */}
      <header className={`sticky top-0 z-50 transition-all duration-300 ${scrolled ? "bg-white shadow-lg" : "bg-white/95 backdrop-blur-sm"}`}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-20">
            {/* Logo */}
            <Link to={createPageUrl("Home")} className="flex items-center gap-3">
              <div className="w-12 h-12 bg-gradient-to-br from-[#1e3a5f] to-[#2d5a8a] rounded-xl flex items-center justify-center shadow-lg">
                <GraduationCap className="w-7 h-7 text-[#f5a623]" />
              </div>
              <div>
                <span className="text-2xl font-bold text-[#1e3a5f]">RETI</span>
                <p className="text-[10px] text-gray-500 -mt-1">Rising East Training Institute</p>
              </div>
            </Link>

            {/* Desktop Navigation */}
            <nav className="hidden lg:flex items-center gap-1">
              {navLinks.map((link) => (
                <Link
                  key={link.page}
                  to={createPageUrl(link.page)}
                  className={`px-4 py-2 rounded-lg font-medium transition-all duration-200 ${
                    currentPageName === link.page
                      ? "text-[#f5a623] bg-[#f5a623]/10"
                      : "text-gray-700 hover:text-[#1e3a5f] hover:bg-gray-100"
                  }`}
                >
                  {link.name}
                </Link>
              ))}
            </nav>

            {/* CTA Button */}
            <div className="hidden lg:flex items-center gap-4">
              <Link
                to={createPageUrl("Contact")}
                className="bg-[#f5a623] hover:bg-[#e09515] text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg shadow-[#f5a623]/30 hover:shadow-xl hover:shadow-[#f5a623]/40 hover:-translate-y-0.5"
              >
                Apply Now
              </Link>
            </div>

            {/* Mobile Menu Button */}
            <button
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              className="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
            >
              {mobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
            </button>
          </div>
        </div>

        {/* Mobile Menu */}
        <AnimatePresence>
          {mobileMenuOpen && (
            <motion.div
              initial={{ opacity: 0, height: 0 }}
              animate={{ opacity: 1, height: "auto" }}
              exit={{ opacity: 0, height: 0 }}
              className="lg:hidden bg-white border-t"
            >
              <div className="px-4 py-4 space-y-2">
                {navLinks.map((link) => (
                  <Link
                    key={link.page}
                    to={createPageUrl(link.page)}
                    onClick={() => setMobileMenuOpen(false)}
                    className={`block px-4 py-3 rounded-lg font-medium transition-colors ${
                      currentPageName === link.page
                        ? "text-[#f5a623] bg-[#f5a623]/10"
                        : "text-gray-700 hover:bg-gray-100"
                    }`}
                  >
                    {link.name}
                  </Link>
                ))}
                <Link
                  to={createPageUrl("Contact")}
                  onClick={() => setMobileMenuOpen(false)}
                  className="block bg-[#f5a623] text-white text-center px-4 py-3 rounded-xl font-semibold mt-4"
                >
                  Apply Now
                </Link>
              </div>
            </motion.div>
          )}
        </AnimatePresence>
      </header>

      {/* Main Content */}
      <main className="flex-grow">{children}</main>

      {/* Footer */}
      <footer className="bg-[#1e3a5f] text-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            {/* About */}
            <div>
              <div className="flex items-center gap-3 mb-6">
                <div className="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                  <GraduationCap className="w-7 h-7 text-[#f5a623]" />
                </div>
                <span className="text-2xl font-bold">RETI</span>
              </div>
              <p className="text-gray-300 leading-relaxed">
                Rising East Training Institute - TEVETA accredited vocational training center. Building skills that open doors to employment and enterprise.
              </p>
            </div>

            {/* Quick Links */}
            <div>
              <h4 className="text-lg font-semibold mb-6 flex items-center gap-2">
                <div className="w-1 h-6 bg-[#f5a623] rounded-full"></div>
                Quick Links
              </h4>
              <ul className="space-y-3">
                {navLinks.map((link) => (
                  <li key={link.page}>
                    <Link
                      to={createPageUrl(link.page)}
                      className="text-gray-300 hover:text-[#f5a623] transition-colors"
                    >
                      {link.name}
                    </Link>
                  </li>
                ))}
              </ul>
            </div>

            {/* Programs */}
            <div>
              <h4 className="text-lg font-semibold mb-6 flex items-center gap-2">
                <div className="w-1 h-6 bg-[#f5a623] rounded-full"></div>
                Programs
              </h4>
              <ul className="space-y-3 text-gray-300">
                <li>Level III Programmes</li>
                <li>Skills Award Programmes</li>
                <li>Short Courses</li>
                <li>Management Training</li>
                <li>Heavy Equipment Operation</li>
              </ul>
            </div>

            {/* Contact */}
            <div>
              <h4 className="text-lg font-semibold mb-6 flex items-center gap-2">
                <div className="w-1 h-6 bg-[#f5a623] rounded-full"></div>
                Contact Us
              </h4>
              <ul className="space-y-4">
                <li className="flex items-start gap-3">
                  <MapPin className="w-5 h-5 text-[#f5a623] flex-shrink-0 mt-1" />
                  <span className="text-gray-300">East Campus, Plot A150, Off Munali Road, P.O Box 33381, Lusaka, Zambia</span>
                </li>
                <li className="flex items-center gap-3">
                  <Phone className="w-5 h-5 text-[#f5a623]" />
                  <a href="tel:0954999900" className="text-gray-300 hover:text-[#f5a623] transition-colors">0954-999900</a>
                </li>
                <li className="flex items-center gap-3">
                  <Mail className="w-5 h-5 text-[#f5a623]" />
                  <a href="mailto:academic@reti.edu.zm" className="text-gray-300 hover:text-[#f5a623] transition-colors">academic@reti.edu.zm</a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        {/* Bottom Bar */}
        <div className="border-t border-white/10">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div className="flex flex-col md:flex-row justify-between items-center gap-4">
              <p className="text-gray-400 text-sm">
                © {new Date().getFullYear()} Rising East Training Institute. All rights reserved.
              </p>
              <div className="flex items-center gap-4">
                <a href="#" className="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-[#f5a623] transition-colors">
                  <Facebook className="w-5 h-5" />
                </a>
                <a href="#" className="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-[#f5a623] transition-colors">
                  <Twitter className="w-5 h-5" />
                </a>
                <a href="#" className="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-[#f5a623] transition-colors">
                  <Instagram className="w-5 h-5" />
                </a>
                <a href="#" className="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-[#f5a623] transition-colors">
                  <Linkedin className="w-5 h-5" />
                </a>
              </div>
            </div>
          </div>
        </div>
      </footer>

      {/* Scroll to Top Button */}
      <AnimatePresence>
        {showScrollTop && (
          <motion.button
            initial={{ opacity: 0, scale: 0.8 }}
            animate={{ opacity: 1, scale: 1 }}
            exit={{ opacity: 0, scale: 0.8 }}
            onClick={scrollToTop}
            className="fixed bottom-8 right-8 w-12 h-12 bg-[#f5a623] text-white rounded-full shadow-lg flex items-center justify-center hover:bg-[#e09515] transition-colors z-50"
          >
            <ChevronUp className="w-6 h-6" />
          </motion.button>
        )}
      </AnimatePresence>
    </div>
  );
}